
export default {
    data() {
        return {
            isCandidateEventAddEditModalActive: false,
            selectedCandidate: null,
            selectedJobPost: null,
            selectedApplicant: null,
            eventSelectedUrl: '',
        }
    },
    methods: {

        lowDetailsModalOpacity(opacity = '0.5') {
            setTimeout(() => {
                $('#candidate-details-modal').css({opacity});
            });
        },
        highDetailsModalOpacity(opacity = '1') {
            setTimeout(() => {
                $('#candidate-details-modal').css({opacity});
            });
        }
    }
}