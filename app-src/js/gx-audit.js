import Vue from './elements';
import Router from 'vue-router';
Vue.use(Router);

import { applyFilters, addFilter, addAction, doAction } from '@wordpress/hooks';



export default class PluginName {
    constructor() {
        this.applyFilters = applyFilters;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.doAction = doAction;
        this.Vue = Vue;
        this.Router = Router;
    }

    $publicAssets(path){
        return (window.gx_object.assets_url + path);
    }

    $get(options) {
        return window.jQuery.get(window.gx_object.ajaxurl, options);
    }

    $adminGet(options) {
        options.action = 'plugin_name_admin_ajax';
        return window.jQuery.get(window.gx_object.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.gx_object.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'plugin_name_admin_ajax';
        return window.jQuery.post(window.gx_object.ajaxurl, options);
    }

    $getJSON(options) {
        return window.jQuery.getJSON(window.gx_object.ajaxurl, options);
    }
}
