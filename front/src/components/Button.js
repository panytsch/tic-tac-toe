import React, {Component} from 'react';

class Button extends Component{
    constructor(props){
        super(props);
        this.state = {
            disabled: false,
        }
    }
    render() {
        return (
        <button
            className={(this.props.classList && this.props.classList.join(' ')) || 'btn btn-light'}
            disabled={this.state.disabled}
            onClick={this.props.onClick || (() => {})}
        >
            {this.props.text}
        </button>);
    }
}

export default Button;