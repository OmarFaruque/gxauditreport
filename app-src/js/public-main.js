window.PluginNameBus = new window.PluginName.Vue();

window.PluginName.Vue.mixin({
    methods: {
        applyFilters: window.PluginName.applyFilters,
        addFilter: window.PluginName.addFilter,
        addAction: window.PluginName.addFilter,
        doAction: window.PluginName.doAction,
        $get: window.PluginName.$get,
        $adminGet: window.PluginName.$adminGet,
        $adminPost: window.PluginName.$adminPost,
        $post: window.PluginName.$post,
        $publicAssets: window.PluginName.$publicAssets
    }
});

// import {routes} from './routes';

// const router = new window.PluginName.Router({
//     routes: window.PluginName.applyFilters('PluginName_global_vue_routes', routes),
//     linkActiveClass: 'active'
// });

import App from './PublicApp';
import GxLists from './GxLists';

if(document.getElementById('gx_ui_public')){
    new window.PluginName.Vue({
        el: '#gx_ui_public',
        render: h => h(App)
    });
}

if(document.getElementById('gx_lists')){
    new window.PluginName.Vue({
        el: '#gx_lists',
        render: h => h(GxLists)
    });
}
