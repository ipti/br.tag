const fs = require('fs');
const vm = require('vm');
const assert = require('assert');

let state = { grade: false, frequency: true, lesson: false };
let clickHandler;
let classroomChange;
let requests = 0;
const notice = { hidden: true, scrollIntoView() {} };
const texts = {};
const attrs = {
    'data-entry-lock-url': '/status',
    'data-entry-lock-types': 'frequency',
    'data-entry-lock-classroom': ''
};
const doc = { hidden: false, addEventListener(name, callback) { if (name === 'click') clickHandler = callback; } };
const windowObject = {};
function $(selector) {
    if (typeof selector === 'function') { selector(); return; }
    if (selector === '.diary-lock-notice') return { each(callback) { callback.call(notice); } };
    if (selector === notice) return {
        attr(name) { return attrs[name]; },
        find(name) { return { text(value) { texts[name] = value; } }; }
    };
    if (selector === '#classroom') return { val() { return '583'; }, on(name, callback) { classroomChange = callback; } };
    if (selector === windowObject) return { on() {} };
    throw new Error('Seletor inesperado: ' + selector);
}
$.ajax = function () {
    requests++;
    return { done(callback) { callback(state); return this; }, fail() { return this; } };
};
vm.runInNewContext(fs.readFileSync('app/modules/classdiary/resources/entry-lock-notice.js', 'utf8'), {
    jQuery: $, document: doc, window: windowObject
});
assert.strictEqual(notice.hidden, false, 'aviso deve aparecer quando a frequência estiver bloqueada');
assert.match(texts['.diary-lock-notice__message'], /frequência/);
let prevented = false;
let stopped = false;
const event = {
    target: { closest(selector) { return selector.indexOf('frequency-checkbox-container') !== -1; } },
    preventDefault() { prevented = true; },
    stopImmediatePropagation() { stopped = true; }
};
clickHandler(event);
assert.ok(prevented && stopped, 'clique bloqueado não deve chegar ao lançamento');
state = { grade: false, frequency: false, lesson: false };
classroomChange();
assert.strictEqual(notice.hidden, true, 'aviso deve desaparecer após a liberação');
prevented = false;
stopped = false;
clickHandler(event);
assert.ok(!prevented && !stopped, 'clique deve funcionar após a liberação');
assert.strictEqual(requests, 2);
console.log('Aviso de frequência: testes aprovados');
