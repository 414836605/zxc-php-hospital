create or replace trigger tg_rkinsert 
after  insert on tab_rk
for each row
declare v_no int;
Begin
  select spbh into v_no from tab_kc 
  where  spbh=:new.spbh;
 update tab_kc set  kcsl=kcsl+:new.rksl 
  where spbh=:new.spbh;
exception
   when no_data_found then
   insert into tab_kc 
   values(:new.spbh,:new.rksl);
end;

create or replace trigger tg_rkinsert 
before insert on tab_rk
for each row
declare v_no int;
Begin
  select spbh into v_no from tab_kc 
  where  spbh=:new.spbh;
 update tab_kc set  kcsl=kcsl+:new.rksl 
  where spbh=:new.spbh;
exception
   when no_data_found then
   insert into tab_kc 
   values(:new.spbh,:new.rksl);
end;