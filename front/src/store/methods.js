const methods = {
    setName: name => dispatch => {
        dispatch({
            type: "SET_NAME",
            data: name
        });
    }
};

export default methods;