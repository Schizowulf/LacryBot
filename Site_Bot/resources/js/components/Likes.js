import React, {useState} from 'react';

export default function Likes() {

    const [likes, setLikes] = useState(5);

    function increment(){
        setLikes(likes + 1)
    }

    function decrement(){
        setLikes(likes - 1)
    }

    return (
        <div className='counter'>
            <h1>{likes}</h1>
            <button onClick={increment}>Increment</button>
            <button onClick={decrement}>Decrement</button>
        </div>
    );
}