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
            1: false,
            2: false,
            3: false,
            4: false,
            5: false,
            6: false,
            7: false,
            8: false,
            9: false
        },
        game: {
            myTurn: null,
            gameId: null
        },
        timeoutId: null,
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
            clearInterval(state.timeoutId);
            return {...state};
        case 'PENDING_GAME':
            state.game.gameId = action.payload.gameId;
            return state;
        case 'SAVE_TIMEOUT':
            state.timeoutId = action.timeoutId;
            return state;
        case 'CLEAR_INTERVAL':
            clearInterval(state.timeoutId);
            return state;
        case 'LEAVE_GAME':
            state.data.type = null;
            state.game.gameId = null;
            state.game.myTurn = null;
            return {...state};
        case 'MAKE_TURN':
            state.currentGame[action.payload.itemNumber] = false;
            state.game.myTurn = false;
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