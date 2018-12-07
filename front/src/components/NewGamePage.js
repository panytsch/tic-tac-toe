import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import Button from './Button';
import LinkButton from './LinkButton';
import methods from '../store/methods';

class StartPage extends React.Component{
    constructor(props){
        super(props);
        console.log(props);
    }

    render() {
        return (
            <div className='flex-column align-content-md-center'>
                <div className='flex-center-wrap' style={{width: '50%'}}>
                    <h3>It's new game page dear {this.props.data.data.name}</h3>
                    <LinkButton
                        text='leave game'
                        classList={['btn', 'btn-light', 'button-main']}
                        route='/'
                    />
                </div>
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => ({
});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(StartPage)
);