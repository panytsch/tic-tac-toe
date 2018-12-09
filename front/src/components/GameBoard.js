import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import BoardItem from "./BoardItem";
import methods from "../store/methods";

class GameBoard extends React.Component{
    constructor(props){
        super(props);
        let timeoutId = setInterval(() => {
            props.searchOpponent(props.data.data.userId, props.data.game.gameId || null);
        }, 2000);
        props.setTimeoutId(timeoutId);
    }
    render() {
        return (
            <div className='game-board-wrap'>
                <div className="point">
                    <BoardItem number={1} text='x'/>
                    <BoardItem number={4} text='o'/>
                    <BoardItem number={7} text='x'/>
                </div>
                <div className="point">
                    <BoardItem number={2} text='o'/>
                    <BoardItem number={5} text='o'/>
                    <BoardItem number={8} text='x'/>
                </div>
                <div className="point">
                    <BoardItem number={3} text='x'/>
                    <BoardItem number={6} text='o'/>
                    <BoardItem number={9} text='x'/>
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
    })
});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(GameBoard)
);