import React, {Component} from 'react';
import {Link} from "react-router-dom";

class Button extends Component{
    render() {
        return (
            <Link
                to={this.props.route || '/'}
                className={(this.props.classList && this.props.classList.join(' ')) || 'btn btn-light'}
                onClick={this.props.onClick || (()=>{})}
            >
                {this.props.text}
            </Link>);
    }
}

export default Button;