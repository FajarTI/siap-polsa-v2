// resources/js/app.js
import '../css/app.css';
import './bootstrap';

import 'select2/dist/css/select2.css';
import $ from 'jquery';
import 'select2';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import route from 'ziggy-js';
import { Ziggy } from '../../vendor/tightenco/ziggy';
window.route = (name, params, absolute) => route(name, params, absolute, Ziggy);

function initSelect2(scope = document) {
    $(scope)
        .find('.select2')
        .each(function () {
            // cegah double init
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2();
            }
        });
}

// pertama kali load
document.addEventListener('DOMContentLoaded', () => initSelect2());

// saat Livewire siap
document.addEventListener('livewire:load', () => {
    initSelect2();

    // Re-init setelah Livewire update DOM (v2 hook)
    if (window.Livewire?.hook) {
        Livewire.hook('message.processed', () => initSelect2());
    }

    // (Alternatif) kalau pakai Livewire Navigate (SPA-like):
    document.addEventListener('livewire:navigated', () => initSelect2());
});
