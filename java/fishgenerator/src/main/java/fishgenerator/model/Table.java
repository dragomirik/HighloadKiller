package fishgenerator.model;

import java.sql.ResultSet;
import java.util.List;

public class Table {
	private String name;
	private List<ForeignKey> foreinKeys;
	private List<Column> columns;
	private boolean filled;

	public Table(String name, List<Column> columns, List<ForeignKey> foreignKeys) {
		this.setName(name);
		this.setForeinKeys(foreignKeys);
		this.columns = columns;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public List<ForeignKey> getForeinKeys() {
		return foreinKeys;
	}

	public void setForeinKeys(List<ForeignKey> foreinKeys) {
		this.foreinKeys = foreinKeys;
	}

	public boolean isFilled() {
		return filled;
	}

	public void setFilled(boolean filled) {
		this.filled = filled;
	}

	public List<Column> getColumns() {
		return columns;
	}

	public void setColumns(List<Column> columns) {
		this.columns = columns;
	}
}
