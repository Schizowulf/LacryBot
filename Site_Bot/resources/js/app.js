//import './bootstrap';
import React from 'react';
//import ReactDOM from 'react-dom'
import {createRoot} from 'react-dom/client'
import Likes from './components/Likes'
import UserOrders from './components/ListUserOrders'
import axios from 'axios';

if (document.getElementById('likes')) {
    let root = createRoot(document.getElementById('likes'))
    root.render(<Likes/>);
}
if (document.getElementById('userOrders')) {
    let root = createRoot(document.getElementById('userOrders'))
    root.render(<UserOrders/>);
}
/*
window.addEventListener('DOMContentLoaded', (event) => {
    var btn = document.getElementById('create-order')
    console.log('tete1', btn)
    if(btn){
        
        console.log('tete')

        
    }
});
*/