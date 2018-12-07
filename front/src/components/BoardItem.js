import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

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
            >
                {number}
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
    withRouter(Item)
);