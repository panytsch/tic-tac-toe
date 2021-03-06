import React from 'react';
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import Button from './Button';
import LinkButton from './LinkButton';
import methods from '../store/methods';

class StartPage extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidMount() {
        this.props.clearInterval();
    }

    render() {
        return (
            <div className='flex-column align-content-md-center'>
                <div className='flex-center-wrap' style={{width: '50%'}}>
                    <Button
                        text={this.props.data.data.name || 'Enter name'}
                        onClick={()=>this.props.data.data.userId
                            ? e => {e.preventDefault()}
                            : this.props.setName(prompt('Your name'))
                        }
                        classList={['btn', 'btn-light', 'button-main']}
                    />
                    <LinkButton
                        text='New game'
                        classList={['btn', 'btn-light', 'button-main']}
                        route='/new-game'
                        onClick={()=>this.props.data.data.userId
                            ? (()=>{})
                            : (this.props.setName(prompt('Your name')))}
                    />
                </div>
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => ({
    setName: data => dispatch(methods.setName(data)),
    clearInterval: () => dispatch({type: 'CLEAR_INTERVAL'})
});

const mapStateToProps = state => ({
    data: state.userData
});

export default connect(mapStateToProps, mapDispatchToProps)(
    withRouter(StartPage)
);