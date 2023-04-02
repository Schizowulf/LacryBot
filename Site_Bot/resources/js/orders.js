import React from 'react';
import {createRoot} from 'react-dom/client'
import UserOrders from './layouts/UserOrders';

if (document.getElementById('userOrders')) {
    let root = createRoot(document.getElementById('userOrders'))
    root.render(<UserOrders/>);
}
