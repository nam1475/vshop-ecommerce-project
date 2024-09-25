import { AbilityBuilder, Ability } from '@casl/ability'

export default function ability () {
    const { can, cannot, buildQueries } = new AbilityBuilder(Ability)
    return {
        can,
        cannot,
        buildQueries
    }
    
    // return build();
}