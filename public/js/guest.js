(() => {
    if (!window.axios) return;
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
})();

