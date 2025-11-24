import './bootstrap';
import './notifications';
import './theme';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start(); 