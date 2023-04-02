import React from 'react';
import {createRoot} from 'react-dom/client'
import ApiCredentials from './components/ApiCredentials';

if (document.getElementById('api-credentials')) {
    let root = createRoot(document.getElementById('api-credentials'))
    root.render(<ApiCredentials/>);
}
