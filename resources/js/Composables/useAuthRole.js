import { usePage } from '@inertiajs/vue3';

export function useAuthRole() {
    const page = usePage();
    const authProps = page.props.auth.user;
    const userRole = authProps.role;

    const hasRole = (requiredRole) => {
        if (!userRole) {
            return false;
        }

        if (Array.isArray(requiredRole)) {
            return requiredRole.map(r => r.toLowerCase()).includes(userRole.name);
        }

        return userRole.name === requiredRole.toLowerCase();
    };

    return {
        userRole,
        hasRole,
    };
}
