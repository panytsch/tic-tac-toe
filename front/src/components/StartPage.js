import React from 'react';

import Button from './Button';

class StartPage extends React.Component{

    render() {
        return (
            <div className='flex-column align-content-md-center'>
                <div className='flex-center-wrap' style={{width: '50%'}}>
                    <Button
                        text='Enter name'
                        onClick={()=>{alert('hi')}}
                    />
                </div>
            </div>
        );
    }
}

export default StartPage;