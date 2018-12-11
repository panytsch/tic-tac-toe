import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import LinkButton from './LinkButton';
import GameBoard from "./GameBoard";
import methods from "../store/methods";

class NewGamePage extends React.Component{
    constructor(props){
        super(props);
        if (!this.props.data.data.userId) {
            this.props.history.push('/');
        }
    }

    render() {
        return (
            <div className='flex-column align-content-md-center'>
                <div className='flex-center-wrap' style={{width: '50%'}}>
                    <GameBoard/>
                    <LinkButton
                        text='Abandon game'
                        classList={['btn', 'btn-light', 'button-main']}
                        route='/'
                        onClick={()=>this.props.leaveGame(this.props.data.data.userId, this.props.data.game.gameId)}
                    />
                </div>
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => ({
    leaveGame: (userId, gameId) => dispatch(methods.leaveGame(userId, gameId))
});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(NewGamePage)
);