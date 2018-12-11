import { createStore, applyMiddleware, combineReducers } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
import thunk from "redux-thunk";

function userData(
    state = {
        data: {
            type: null, // 'x' or 'o'
            name: null,
            userId: null,
        },
        currentGame: {
            1: null, //null, 'x' or 'o'
            2: null,
            3: null,
            4: null,
            5: null,
            6: null,
            7: null,
            8: null,
            9: null
        },
        game: {
            myTurn: null,
            gameId: null
        },
        timeoutId: null,
        isMyTurnTimeoutId: null
    },
    action
) {
    switch (action.type) {
        case "SET_NAME":
            state.data.name = action.payload.name;
            state.data.userId = action.payload.userId;
            return { ...state };
        case 'FOUNDED_GAME':
            state.data.type = action.payload.type;
            state.game.myTurn = action.payload.type === 'o';
            state.game.gameId = action.payload.gameId;
            if (!state.game.myTurn) {
                state.isMyTurnTimeoutId = true;
            }
            clearInterval(state.timeoutId);
            state.timeoutId = null;
            return {...state};
        case 'PENDING_GAME':
            state.game.gameId = action.payload.gameId;
            return state;
        case 'SAVE_TIMEOUT_OTHER':
            clearInterval(state.isMyTurnTimeoutId);
            state.isMyTurnTimeoutId = action.timeoutId;
            return state;
        case 'SAVE_TIMEOUT':
            clearInterval(state.timeoutId);
            state.timeoutId = action.timeoutId;
            return state;
        case 'CLEAR_INTERVAL':
            clearInterval(state.timeoutId);
            state.timeoutId = null;
            return state;
        case 'LEAVE_GAME':
            state.data.type = null;
            state.game.gameId = null;
            state.game.myTurn = null;
            return {...state};
        case 'MAKE_TURN':
            state.currentGame[action.payload.itemNumber] = state.data.type;
            state.game.myTurn = false;
            state.timeoutId = action.payload.timeoutId;
            return {...state};
        case 'MY_TURN_FETCHED':
            action.payload.me && action.payload.me.map(i=>{
                state.currentGame[i] = state.data.type
            });
            action.payload.opponent && action.payload.opponent.map(i=>{
                state.currentGame[i] = state.data.type === 'x' ? 'o' : 'x'
            });
            state.game.myTurn = true;
            clearInterval(state.isMyTurnTimeoutId);
            state.isMyTurnTimeoutId = null;
            return {...state};
        default:
            return state;
    }
}

const reducers = combineReducers({ userData });

const store = createStore(
    reducers,
    composeWithDevTools(applyMiddleware(thunk))
);

export default store;