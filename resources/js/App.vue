<script setup>
</script>

<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <RouterLink class="navbar-brand" to="/">
                    Econet Companies
                </RouterLink>

                <div class="navbar-nav">
                    <RouterLink class="nav-link" to="/companies">
                        Empresas
                    </RouterLink>
                </div>
            </div>
        </nav>

        <RouterView />
    </div>
</template>