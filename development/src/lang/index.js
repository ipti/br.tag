/**
 * App Language Provider
 * Add more locales here
 */
import { addLocaleData } from 'react-intl';
import enLang from './entries/en-US';
import frLang from './entries/fr_FR';
import zhLang from './entries/zh-Hans-CN';
import arLang from './entries/ar_SA';
import heLang from './entries/he_HE';
import deLang from './entries/de_DE';
import ruLang from './entries/ru_RU';
import esLang from './entries/es_ES';
import jaLang from './entries/ja_JA';
import koLang from './entries/ko_KO';
import itLang from './entries/it_IT';
import huLang from './entries/hu_HU';
import ptLang from './entries/pt_BR';

const AppLocale = {
    en: enLang,
    fr: frLang,
    zh: zhLang,
    ar: arLang,
    he: heLang,
    de: deLang,
    ru: ruLang,
    es: esLang,
    ja: jaLang,
    ko: koLang,
    it: itLang,
    hu: huLang,
    pt: ptLang
};

addLocaleData(AppLocale.en.data);
addLocaleData(AppLocale.fr.data);
addLocaleData(AppLocale.zh.data);
addLocaleData(AppLocale.ar.data);
addLocaleData(AppLocale.he.data);
addLocaleData(AppLocale.de.data);
addLocaleData(AppLocale.ru.data);
addLocaleData(AppLocale.es.data);
addLocaleData(AppLocale.ja.data);
addLocaleData(AppLocale.ko.data);
addLocaleData(AppLocale.it.data);
addLocaleData(AppLocale.hu.data);
addLocaleData(AppLocale.pt.data);

export default AppLocale;
