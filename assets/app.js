/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import {createApp} from 'vue';
import App from './components/App';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

let app = createApp( App );
app.mount('#app');
