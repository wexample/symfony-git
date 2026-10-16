import commit from '../data/entity/commit.json';

type EntitySchema = { name: string };

export default function getGeneratedEntitySchemas(): Record<string, EntitySchema> {
  return {
    [commit.name]: commit,
  };
}
