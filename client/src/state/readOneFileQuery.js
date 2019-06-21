import { graphql } from 'react-apollo';
import gql from 'graphql-tag';

// GraphQL query for retrieving the version history of a specific object. The results of
// the query must be set to the "versions" prop on the component that this HOC is
// applied to for binding implementation.
const query = gql`
query ReadHistoryViewerFile ($file_id: ID!, $limit: Int!, $offset: Int!) {
  readOneVersionedFile(
    Versioning: {
      Mode: LATEST
    },
    ID: $file_id
  ) {
    ID
    Versions (limit: $limit, offset: $offset) {
      pageInfo {
        totalCount
      }
      edges {
        node {
          Version
          Author {
            FirstName
            Surname
          }
          Publisher {
            FirstName
            Surname
          }
          Published
          LiveVersion
          LatestDraftVersion
          LastEdited
        }
      }
    }
  }
}
`;

const config = {
  options({ recordId, limit, page }) {
    return {
      variables: {
        limit,
        offset: ((page || 1) - 1) * limit,
        file_id: recordId,
      },
    };
  },
  props(
    {
      data: {
        error,
        refetch,
        readOneVersionedFile,
        loading: networkLoading,
      },
      ownProps: {
        actions = {
          versions: {},
        },
        limit,
        recordId,
      },
    },
  ) {
    const versions = readOneVersionedFile || null;

    const errors = error && error.graphQLErrors &&
      error.graphQLErrors.map(graphQLError => graphQLError.message);

    return {
      loading: networkLoading || !versions,
      versions,
      graphQLErrors: errors,
      actions: {
        ...actions,
        versions: {
          ...versions,
          goToPage(page) {
            refetch({
              offset: ((page || 1) - 1) * limit,
              limit,
              file_id: recordId,
            });
          },
        },
      },
    };
  },
};

export { query, config };

export default graphql(query, config);
