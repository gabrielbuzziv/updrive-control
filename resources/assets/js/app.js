/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Imports
 */
import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import './broadcast'

window.Vue = Vue
Vue.use(VueAxios, axios)

/**
 * Components
 */
import Hive from './components/Hive'

Vue.component('hive', Hive)