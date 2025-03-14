import { createStore } from 'vuex'

export const store = createStore({
    state() {
        return {
            menuStates: {}, 
            activeRoute: ''
        }
    },
    mutations: {
        toggleMenu(state, menuId) {
            Object.keys(state.menuStates).forEach(key => {
                if (key !== menuId) {
                    state.menuStates[key] = false
                }
            })            
            state.menuStates[menuId] = !state.menuStates[menuId]            
        },
        setUserSubMenuState(state, isOpen) {
          state.isUserSubMenuOpen = isOpen
        },
        setActiveRoute(state, route) {
          state.activeRoute = route
        }
    },
    actions: {
        toggleMenu({ commit }, menuId) {
            commit('toggleMenu', menuId)
        },
        setActiveRoute({ commit }, route) {
          commit('setActiveRoute', route)
        }
    },
    getters: {
        isMenuOpen : (state) => (menuId) => {
            return state.menuStates[menuId] || false
        },
        activeRoute: (state) => state.activeRoute 
    }
})