// ==========================================================>> Custom Library
import { NavigationItem } from 'helpers/components/navigation';
let isAdmin = true;

export const defaultNavigation: NavigationItem[] = [
    //===================================>> Dashboard
    {
        id: 'dashboard',
        title: 'ផ្ទាំងព័ត៌មាន',
        type: 'basic',
        icon: 'mat_outline:dashboard',
        link: '/dashboard',
    },
    //===================================>> Create Menu "Product" in navigation
    {
        hidden() {
            isAdmin = true;
            if (localStorage.getItem('role') == 'Admin') {
                isAdmin = false;
            }
            return isAdmin;
        },
        id: 'product',
        title: 'ផលិតផល',
        type: 'collapsable',
        icon: 'mat_solid:shop_two',
        children: [
            {
                id: 'all',
                title: 'ទាំងអស់',
                type: 'basic',
                icon: 'heroicons_solid:chevron-right',
                link: 'product/all',
            },
            {
                id: 'product type',
                title: 'ប្រភេទ',
                type: 'basic',
                icon: 'heroicons_solid:chevron-right',
                link: 'product/types',
            },
        ],
    },
    {
        hidden() {
            isAdmin = true;
            if (localStorage.getItem('role') == 'Admin') {
                isAdmin = false;
            }
            return isAdmin;
        },
        id: 'user',
        title: 'អ្នកប្រើប្រាស់',
        type: 'basic',
        icon: 'mat_outline:people',
        link: '/users',
    },
    //===================================>> Create Menu "POS" in navigation
    {
        id: 'pos',
        title: 'ការបញ្ជាទិញ',
        type: 'basic',
        icon: 'mat_solid:desktop_mac',
        link: '/pos',
    },
    //===================================>> Create Menu 'Sale' in navigation
    {
        id: 'sale',
        title: 'ការលក់',
        type: 'basic',
        icon: 'mat_solid:shopping_cart',
        link: '/sales',
    },
    //===================================>>Create menu my-profile in navigagtion
    {
        id: 'profile',
        title: 'គណនី',
        type: 'basic',
        icon: 'mat_outline:person',
        link: '/my-profile',
    },
];
