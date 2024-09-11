<script>
export default {
    template: `
        <select class="w-full">        
            <option>Launch!</option>
            <option>Wow!</option>
        </select>
    `,
    methods: {
        buttonClicked() {
            alert('Software Launched');
        },
    },
};
</script>