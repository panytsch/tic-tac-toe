import { createStore, applyMiddleware, combineReducers } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
import thunk from "redux-thunk";

function userData(
    state = {},
    action
) {
    switch (action.type) {
        case "ADD_BOARD":
            state.data[state.user.nickname][action.payload.id] = action.payload;
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