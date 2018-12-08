import axios from 'axios';

const host = 'http://localhost:8000/api/';
const methods = {
    host: host,
    setName: name => dispatch => {
        axios
            .post(`${host}users/new`,{name: name})
            .then(({data}) => {
                if (data.status){
                    dispatch({
                        type: "SET_NAME",
                        payload: {
                            name: name,
                            userId: data.userId
                        },
                    });
                }
            })
    }
};

export default methods;