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
    },
    searchOpponent: (userId, gameId) => dispatch => {
        axios.post(`${host}games/join`,{userId:userId, gameId: gameId})
            .then(({data}) => {
                console.log(data);
                if (data.status && data.type && data.gameId) {
                    dispatch({
                        type: 'FOUNDED_GAME',
                        payload: {
                            gameId: data.gameId,
                            type: data.type
                        }
                    })
                } else if (data.pending && data.gameId){
                    dispatch({
                        type: 'PENDING_GAME',
                        payload: {
                            gameId: data.gameId
                        }
                    })
                }
            })
    },
    leaveGame: (userId, gameId) => dispatch => {
        axios.post(`${host}games/leave`, {userId: userId, gameId: gameId})
            .then(() => {
                dispatch({
                    type: 'LEAVE_GAME',
                })
            })
    }
};

export default methods;