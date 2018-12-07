import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import LinkButton from './LinkButton';
import GameBoard from "./GameBoard";

class NewGamePage extends React.Component{
    constructor(props){
        super(props);
        // if (!this.props.data.data.name) {
        //     this.props.history.push('/');
        // }
    }

    render() {
        return (
            <div className='flex-column align-content-md-center'>
                <div className='flex-center-wrap' style={{width: '50%'}}>
                    <GameBoard/>
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
    withRouter(NewGamePage)
);