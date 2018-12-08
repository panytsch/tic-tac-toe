import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import BoardItem from "./BoardItem";

class GameBoard extends React.Component{
    constructor(props){
        super(props);
        console.log(props);
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

});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(GameBoard)
);