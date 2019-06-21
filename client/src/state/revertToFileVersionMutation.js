import { graphql } from 'react-apollo';
import gql from 'graphql-tag';

const mutation = gql`
mutation revertFileToVersion($id:ID!, $fromStage:VersionedStage!, $toStage:VersionedStage!, $fromVersion:Int!) {
  copyFileToStage(Input: {
    ID: $id
    FromVersion: $fromVersion
    FromStage: $fromStage
    ToStage: $toStage
  }) {
    ID
  }
}
`;

const config = {
  props: ({ mutate, ownProps: { actions } }) => {
    const revertToVersion = (id, fromVersion, fromStage, toStage) => mutate({
      variables: {
        id,
        fromVersion,
        fromStage,
        toStage,
      },
    });

    return {
      actions: {
        ...actions,
        revertToVersion,
      },
    };
  },
  options: {
    // Refetch versions after mutation is completed
    refetchQueries: ['ReadHistoryViewerFile'],
  },
};

export { mutation, config };

export default graphql(mutation, config);
