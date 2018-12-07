import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import LinkButton from './LinkButton';
import methods from '../store/methods';
import BoardItem from "./BoardItem";

class GameBoard extends React.Component{
    constructor(props){
        super(props);
        console.log(props);
    }

    static createBoard() {
        let board = [];
        for (let i = 1; i === 9; i++){
            board.push(<BoardItem
                text={i}
            />)
        }
        return board;
    };
    render() {
        return (
            <div className='game-board-wrap'>
                {/*{GameBoard.createBoard().join('')}*/}
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

});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(GameBoard)
);