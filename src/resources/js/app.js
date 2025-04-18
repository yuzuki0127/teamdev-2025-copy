import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import Splide from '@splidejs/splide';
import '@splidejs/splide/dist/css/splide.min.css';

window.Splide = Splide;
window.Alpine = Alpine;
window.Swal = Swal;

import './adopting';
import './analysis';
import './visDetails';
import './action';
import './dashboard';
import './create';
import './survey';

Alpine.start();
