import AbstractApiRepository from '@wexample/js-api-entity/Common/AbstractApiRepository';
import Commit from '../Entity/Commit.js';

export default class CommitRepository extends AbstractApiRepository<Commit> {
  static getEntityType() {
    return Commit;
  }
}
