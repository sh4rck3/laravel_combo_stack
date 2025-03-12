export default {
    install: (app) => {
      // Helper para verificar permissões
      app.config.globalProperties.$can = function(permission) {
        // Verifica se o usuário está autenticado
        if (!this.$page.props.auth.user) {
          return false;
        }
        
        // Verifica se o usuário tem a permissão
        return this.$page.props.auth.user.permissions.includes(permission);
      };
      
      // Helper para verificar papéis
      app.config.globalProperties.$hasRole = function(role) {
        // Verifica se o usuário está autenticado
        if (!this.$page.props.auth.user) {
          return false;
        }
        
        // Verifica se o usuário tem o papel
        return this.$page.props.auth.user.roles.includes(role);
      };
  
      // Helper para verificar qualquer uma das permissões
      app.config.globalProperties.$canAny = function(permissions) {
        if (!this.$page.props.auth.user) {
          return false;
        }
        
        return permissions.some(permission => 
          this.$page.props.auth.user.permissions.includes(permission)
        );
      };
  
      // Helper para verificar qualquer um dos papéis
      app.config.globalProperties.$hasAnyRole = function(roles) {
        if (!this.$page.props.auth.user) {
          return false;
        }
        
        return roles.some(role => 
          this.$page.props.auth.user.roles.includes(role)
        );
      };
    }
  };