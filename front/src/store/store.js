import { createStore, applyMiddleware, combineReducers } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
import thunk from "redux-thunk";

function userData(
    state = {
        data: {
            type: 'x',
            name: null,
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
            myTurn: true
        }
    },
    action
) {
    switch (action.type) {
        case "SET_NAME":
            state.data.name = action.name;
            return { ...state };
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