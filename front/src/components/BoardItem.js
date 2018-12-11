import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";
import methods from "../store/methods";

const types = {
    1: [0,1,1,0],
    2: [0,1,1,1],
    3: [0,0,1,1],
    4: [1,1,1,0],
    5: [1,1,1,1],
    6: [1,0,1,1],
    7: [1,1,0,0],
    8: [1,1,0,1],
    9: [1,0,0,1],
};

class Item extends React.Component {
    constructor(props){
        super(props);
    }

    myTurn(){
        let timeoutId = setInterval( () => {
                this.props.isMyTurn(this.props.data.data.userId, this.props.data.game.gameId,);
            },1000);
        this.props.makeTurn(
            this.props.data.data.userId,
            this.props.data.game.gameId,
            this.props.number,
            timeoutId
        );

    }

    render() {
        const {number} = this.props;
        return (
            <div
                className='game-board-item'
                style={{
                    borderTop: types[number][0] && '2px solid black',
                    borderRight: types[number][1] && '2px solid black',
                    borderBottom: types[number][2] && '2px solid black',
                    borderLeft: types[number][3] && '2px solid black',
                }}
                onClick={(this.props.data.game.myTurn && !this.props.data.currentGame[number])
                    ? () => this.myTurn() : () => {}
                }
            >
                {this.props.data.currentGame[number]}
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    makeTurn: (userId, gameId, itemNumber, timeoutId) => dispatch(methods.makeTurn(userId, gameId, itemNumber, timeoutId)),
    isMyTurn: (userId, gameId) => dispatch(methods.isMyTurn(userId, gameId)),
});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(Item)
);