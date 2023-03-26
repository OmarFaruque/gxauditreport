import Dashboard from './Components/Dashboard';
import Settings from './Components/Settings';


export const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/settings',
        name: 'settings',
        component: Settings
    }
];
