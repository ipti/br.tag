"""R0-SQL-08: smoke HTTP de classroom/default/batchupdatEnrollment (id da turma na URL).

Compara, nas mesmas requisições e logado como usuário comum de uma base de DESENVOLVIMENTO,
a instância A (código antigo) com a B (código corrigido). Só faz GET; não usa o ramo de gravação.

Variáveis de ambiente (nada de credencial no repositório):
  TAG_PW          senha do usuário (obrigatória)
  TAG_USER        usuário (padrão: admin)
  TAG_BASE_A      URL da instância antiga      (padrão: http://127.0.0.1:80)
  TAG_BASE_B      URL da instância corrigida   (padrão: http://127.0.0.1:8081)
  TAG_CLASSROOM   id de uma turma existente com matrículas (obrigatória)
  TAG_HOST        cabeçalho Host que seleciona o tenant (padrão: localhost, que cai no demo)

Saída: tabela por caso. Código de saída 1 se a instância B falhar em algum critério do card.
"""
import hashlib
import http.cookiejar
import os
import re
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from datetime import date

PW = os.environ["TAG_PW"]
USER = os.environ.get("TAG_USER", "admin")
HOST = os.environ.get("TAG_HOST", "localhost")
T = os.environ["TAG_CLASSROOM"]
TIMEOUT = int(os.environ.get("TAG_TIMEOUT", "30"))  # segundos por requisição
FAST = float(os.environ.get("TAG_FAST_LIMIT", "3"))  # "sem demora": mais rápido que o SLEEP(3) do payload
TARGETS = {
    "A": os.environ.get("TAG_BASE_A", "http://127.0.0.1:80"),
    "B": os.environ.get("TAG_BASE_B", "http://127.0.0.1:8081"),
}
ROUTE = "classroom/default/batchupdatEnrollment"


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None


def opener():
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()), NoRedirect())


def call(op, base, query, data=None):
    url = f"{base}/?{urllib.parse.urlencode(query)}"
    body = urllib.parse.urlencode(data).encode() if data is not None else None
    started = time.monotonic()
    try:
        resp = op.open(urllib.request.Request(url, data=body, headers={"Host": HOST}), timeout=TIMEOUT)
        status, text, headers = resp.status, resp.read().decode("utf-8", "replace"), resp.headers
    except urllib.error.HTTPError as err:
        status, text, headers = err.code, err.read().decode("utf-8", "replace"), err.headers
    except (TimeoutError, OSError):
        # a resposta passou do limite: no código antigo, `id OR 1=1` monta a página de todas as turmas
        status, text, headers = 0, "", {}
    return status, text, headers, time.monotonic() - started


def login(base):
    op = opener()
    call(op, base, {"r": "site/login"})
    for year in (str(date.today().year), "0"):
        status, _, headers, _ = call(op, base, {"r": "site/login"}, {"LoginForm[username]": USER, "LoginForm[password]": PW, "LoginForm[year]": year})
        if status == 302 and "login" not in (headers.get("Location") or ""):
            return op
    raise SystemExit(f"login falhou em {base}")


def summary(status, text, secs):
    rows = len(re.findall(r"<tr", text))
    digest = hashlib.sha1(text.encode()).hexdigest()[:8]
    return {"http": status, "linhas": rows, "sha": digest, "seg": round(secs, 1)}


# O caso que vaza todas as turmas fica por último: no código antigo ele monta a página de todas
# as matrículas e pode demorar minutos.
CASES = [
    ("id valido", T),
    ("id) OR (1=1", f"{T}) OR (1=1"),
    ("id AND SLEEP(3)", f"{T} AND SLEEP(3)"),
    ("abc", "abc"),
    ("id OR 1=1", f"{T} OR 1=1"),
]

results = {}
for name, base in TARGETS.items():
    results[name] = {}
    for label, value in CASES:
        if name == "A" and os.environ.get("TAG_SKIP_HEAVY_A") and label in ("id AND SLEEP(3)", "id OR 1=1"):
            # evita sobrecarregar a base com os casos que levam minutos no código antigo
            results[name][label] = {"http": -1, "linhas": 0, "sha": "-", "seg": 0}
            continue
        # sessão nova por caso: o PHP serializa requisições da mesma sessão (lock), e uma resposta
        # lenta de um caso travaria os seguintes
        r = call(login(base), base, {"r": ROUTE, "id": value})
        results[name][label] = summary(r[0], r[1], r[3])

sem_sessao = call(opener(), TARGETS["B"], {"r": ROUTE, "id": T})
print(f"controle sem sessão em B: HTTP {sem_sessao[0]} -> {sem_sessao[2].get('Location')}\n")
print(f"{'caso':<18}{'A (antigo)':<40}B (corrigido)")
for label, _ in CASES:
    a, b = results["A"][label], results["B"][label]
    fmt = lambda r: ("nao executado" if r["http"] == -1 else f"TIMEOUT>{TIMEOUT}s" if r["http"] == 0 else f"http={r['http']} linhas={r['linhas']} {r['seg']}s #{r['sha']}")
    print(f"{label:<18}{fmt(a):<40}{fmt(b)}")

valid_a, valid_b = results["A"]["id valido"], results["B"]["id valido"]
ok = valid_a["http"] == 200 and valid_a["sha"] == valid_b["sha"]
for label in ("id OR 1=1", "id) OR (1=1", "id AND SLEEP(3)", "abc"):
    r = results["B"][label]
    ok = ok and r["http"] == 400 and r["seg"] < FAST

# --- Segunda action da mesma varredura: actionUpdateComplementaryActivity (POST, ids de atividade) ---
ROUTE2 = "classroom/default/updateComplementaryActivity"
POST_CASES = [
    # POST realista do formulário: os seis campos, com "null" nos vazios
    ("sem selecao", {f"Classroom[complementary_activity_type_{i}]": "null" for i in range(1, 7)}),
    ("so tipo 1 = null", {"Classroom[complementary_activity_type_1]": "null"}),
    ("2 ids validos", {"Classroom[complementary_activity_type_1]": os.environ.get("TAG_ACT1", "10101"), "Classroom[complementary_activity_type_2]": os.environ.get("TAG_ACT2", "10102")}),
    ('aspa solta 1"', {"Classroom[complementary_activity_type_1]": '1"'}),
    ('OR "1"="1', {"Classroom[complementary_activity_type_1]": '10101" OR "1"="1'}),
]
post = {name: {} for name in TARGETS}
for name, base in TARGETS.items():
    for label, data in POST_CASES:
        r = call(login(base), base, {"r": ROUTE2}, data)
        post[name][label] = {**summary(r[0], r[1], r[3]), "opcoes": r[1].count("<option")}
print(f"\n{'actionUpdateComplementaryActivity':<34}{'A (antigo)':<34}B (corrigido)")
for label, _ in POST_CASES:
    a, b = post["A"][label], post["B"][label]
    f = lambda r: "TIMEOUT" if r["http"] == 0 else f"http={r['http']} opcoes={r['opcoes']} #{r['sha']}"
    print(f"{label:<34}{f(a):<34}{f(b)}")
ok = ok and all(post["B"][l]["http"] == 200 for l, _ in POST_CASES)
ok = ok and post["A"]["2 ids validos"]["sha"] == post["B"]["2 ids validos"]["sha"]
ok = ok and post["A"]["sem selecao"]["sha"] == post["B"]["sem selecao"]["sha"]
ok = ok and post["B"]['aspa solta 1"']["sha"] == post["B"]["sem selecao"]["sha"]
print("\nRESULTADO:", "aprovado" if ok else "REPROVADO")
sys.exit(0 if ok else 1)
