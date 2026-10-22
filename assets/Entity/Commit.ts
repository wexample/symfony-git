import AbstractApiEntity from '@wexample/js-api-entity/Common/AbstractApiEntity';
import schema from '../data/entity/commit.json';

export default class Commit extends AbstractApiEntity {
  static readonly entityName = 'commit';

  static retrieveEntitySchema() {
    return schema;
  }
}
