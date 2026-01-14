export const ProgressionBarMixin = {
    methods: {
        calculateBarWidth(currentStepNumber) {
            let config = JSON.parse(this.installerConfig),
                optionalSteps = Object.values(config.include_option).filter(x => x === true).length,
                totalSteps = 3 + optionalSteps;

            return (100 / totalSteps) * currentStepNumber;
        }
    }
}