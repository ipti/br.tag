"""Compare dedicated Yii test servers using synthetic identities and MySQL data."""
import http.cookiejar
import urllib.error
import urllib.parse
import urllib.request

BASELINE = 'http://127.0.0.1:18086/'
PATCHED = 'http://127.0.0.1:18085/'


def request(opener, base, route, data=None):
    url = base + '?' + urllib.parse.urlencode({'r': route})
    encoded = None if data is None else urllib.parse.urlencode(data).encode()
    try:
        with opener.open(url, data=encoded, timeout=10) as response:
            return response.status, response.read()
    except urllib.error.HTTPError as error:
        return error.code, error.read()


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


def new_client():
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()), NoRedirect())


old = new_client()
new = new_client()
for client, base in [(old, BASELINE), (new, PATCHED)]:
    status, _ = request(client, base, 'enrollment/getModalities', {'Stage': '1'})
    assert status in (302, 403), 'visitante deve ser bloqueado pelo accessControl'
    assert request(client, base, 'fixture/login') == (200, b'authenticated')

old_outputs = {}
for stage in ('0', '1', '2', '99'):
    before_status, before = request(old, BASELINE, 'enrollment/getModalities', {'Stage': stage})
    after_status, after = request(new, PATCHED, 'enrollment/getModalities', {'Stage': stage})
    assert before_status == after_status == 200 and before == after, 'saída válida mudou para Stage=' + stage
    old_outputs[stage] = before
assert b'data-associated-stage' in old_outputs['1']
status, injected = request(old, BASELINE, 'enrollment/getModalities', {'Stage': '1 OR 1=1'})
assert status == 200 and injected == old_outputs['0'], 'baseline não reproduziu ampliação por SQL injection'
for payload in ('1 OR 1=1', '1) OR (1=1', 'abc', ''):
    status, _ = request(new, PATCHED, 'enrollment/getModalities', {'Stage': payload})
    assert status == 400, 'entrada inválida deve responder 400: ' + payload
for payload in ({'Stage[]': '1'}, {}):
    status, _ = request(new, PATCHED, 'enrollment/getModalities', payload)
    assert status == 400, 'array ou valor ausente deve responder 400'
print('R0-SQL-04: HTTP Yii, filtro, baseline de injeção e MySQL isolado aprovados')
