/* global window */
import Injector from 'lib/Injector';
import readOneFileQuery from '../state/readOneFileQuery';
import revertToFileVersionMutation from '../state/revertToFileVersionMutation';

window.document.addEventListener('DOMContentLoaded', () => {
  // Register GraphQL operations with Injector as transformations
  Injector.transform(
    'File-history',
    (updater) => {
      updater.component(
        'HistoryViewer',
        readOneFileQuery,
        'AssetHistoryViewer',
      );
    },
  );

  Injector.transform(
    'File-history-revert',
    (updater) => {
      updater.component(
        'HistoryViewerToolbar.VersionedAdmin.HistoryViewer.File.HistoryViewerVersionDetail',
        revertToFileVersionMutation,
        'FileRevertMutation',
      );
    },
  );
});
