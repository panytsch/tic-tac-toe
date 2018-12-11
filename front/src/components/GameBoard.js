import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import BoardItem from "./BoardItem";
import methods from "../store/methods";
import Button from "./Button";

class GameBoard extends React.Component{
    constructor(props){
        super(props);
        let timeoutId = setInterval(() => {
            props.searchOpponent(props.data.data.userId, props.data.game.gameId || null);
        }, 2000);
        props.setTimeoutId(timeoutId);
    }

    componentDidUpdate() {
        if (!this.props.data.game.myTurn && this.props.data.isMyTurnTimeoutId === true){
            let timeoutId = setInterval(() => {
                this.props.isMyTurn(this.props.data.data.userId, this.props.data.game.gameId);
            }, 1000);
            this.props.setTimeoutTurnId(timeoutId);
        }
    }

    render() {
        const {pat, winner, opponentName} = this.props.data.game;
        return (
            <div>
                {(this.props.data.data.name && opponentName && !pat && !winner)
                && <Button
                    text={`${this.props.data.data.name} vs ${this.props.data.game.opponentName}`}
                    classList={['btn', 'btn-light', 'button-main']}
                />}
                {winner
                && <Button
                    text={`${winner} WIN!!!`}
                    classList={['btn', 'btn-light', 'button-main']}
                />}
                {pat
                && <Button
                    text={'The game is a draw'}
                    classList={['btn', 'btn-light', 'button-main']}
                />}
                {this.props.data.data.type && <h5>Your letter is {this.props.data.data.type}</h5>}
                {(!pat && !winner) && (this.props.data.game.myTurn ? <p>Your turn</p> : <p>Opponent`s turn</p>)}
                {(pat || winner) &&
                <Button
                    text={'The game is a draw'}
                    onClick={()=>{
                        let timeoutId = setInterval(() => {
                            this.props.searchOpponent(this.props.data.data.userId, this.props.data.game.gameId || null);
                        }, 2000);
                        this.props.newGame(timeoutId);
                    }}
                    classList={['btn', 'btn-light', 'button-main']}
                />}
                <div className='game-board-wrap'>
                    <div className="point">
                        <BoardItem number={1}/>
                        <BoardItem number={4}/>
                        <BoardItem number={7}/>
                    </div>
                    <div className="point">
                        <BoardItem number={2}/>
                        <BoardItem number={5}/>
                        <BoardItem number={8}/>
                    </div>
                    <div className="point">
                        <BoardItem number={3}/>
                        <BoardItem number={6}/>
                        <BoardItem number={9}/>
                    </div>
                </div>
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => ({
    searchOpponent: (userId, gameId) => dispatch(methods.searchOpponent(userId, gameId)),
    setTimeoutId: timeoutId => dispatch({
        type: 'SAVE_TIMEOUT',
        timeoutId: timeoutId
    }),
    setTimeoutTurnId: timeoutId => dispatch({
        type: 'SAVE_TIMEOUT_OTHER',
        timeoutId: timeoutId
    }),
    isMyTurn: (userId, gameId) => dispatch(methods.isMyTurn(userId, gameId)),
    newGame: (timeoutId) => dispatch({
        type:'NEW_GAME',
        payload: {
            timeoutId: timeoutId
        }
    })

});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(GameBoard)
);