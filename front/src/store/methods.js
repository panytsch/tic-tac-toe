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
                            type: data.type,
                            opponentName: data.opponentName
                        }
                    });
                } else if (data.pending && data.gameId){
                    dispatch({
                        type: 'PENDING_GAME',
                        payload: {
                            gameId: data.gameId
                        }
                    });
                }
            })
    },
    leaveGame: (userId, gameId) => dispatch => {
        axios.post(`${host}games/leave`, {userId: userId, gameId: gameId})
            .then(() => {
                dispatch({
                    type: 'LEAVE_GAME',
                });
            })
    },
    makeTurn: (userId, gameId, itemNumber, timeoutId) => dispatch => {
        axios.post(`${host}games/turn`,
            {
                userId: userId,
                gameId: gameId,
                itemNumber: itemNumber
            })
            .then(({data}) => {
                if (data.status){
                    if (data.win){
                        dispatch({
                            type: 'GAME_HAS_WINNER',
                            payload: {
                                winner: data.winner
                            }
                        });
                    } else if (data.pat) {
                        dispatch({
                            type: 'PAT_GAME'
                        });
                    }
                    dispatch({
                        type: 'MAKE_TURN',
                        payload: {
                            itemNumber: itemNumber,
                            timeoutId: timeoutId
                        }
                    });
                }
            })
        ;
    },
    isMyTurn: (userId, gameId) => dispatch => {
        axios.get(`${host}games/request-to-turn`, {
            params: {
                userId: userId,
                gameId: gameId
            }
        })
            .then(({data}) => {
                if (data.status){
                    dispatch({
                        type: 'MY_TURN_FETCHED',
                        payload: {
                            me: data.data.me,
                            opponent: data.data.opponent
                        }
                    })
                }
            })
    }
};

export default methods;