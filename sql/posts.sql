begin;

create table Posts (
  id CHAR(36) not null,

  title varchar(255) not null,
  body text not null,
  created_at timestamp not null,
  modified_at timestamp not null,

  author CHAR(36) not null references authors(id) on delete cascade,

  primary key (id)
);

commit;
