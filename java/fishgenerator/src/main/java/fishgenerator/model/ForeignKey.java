package fishgenerator.model;

public class ForeignKey {
	private Table pkTable;
	private String pkTableName;
	private String pkColumnName;

	private Table fkTable;
	private String fkTableName;
	private String fkColumnName;

	public ForeignKey(String pkTableName, String pkColumnName,
			String fkTableName, String fkColumnName) {
		this.pkTableName = pkTableName;
		this.pkColumnName = pkColumnName;

		this.fkTableName = fkTableName;
		this.fkColumnName = fkColumnName;
	}

	public Table getPkTable() {
		return pkTable;
	}

	public void setPkTable(Table pkTable) {
		this.pkTable = pkTable;
	}

	public String getPkTableName() {
		return pkTableName;
	}

	public void setPkTableName(String pkTableName) {
		this.pkTableName = pkTableName;
	}

	public String getPkColumnName() {
		return pkColumnName;
	}

	public void setPkColumnName(String pkColumnName) {
		this.pkColumnName = pkColumnName;
	}

	public Table getFkTable() {
		return fkTable;
	}

	public void setFkTable(Table fkTable) {
		this.fkTable = fkTable;
	}

	public String getFkTableName() {
		return fkTableName;
	}

	public void setFkTableName(String fkTableName) {
		this.fkTableName = fkTableName;
	}

	public String getFkColumnName() {
		return fkColumnName;
	}

	public void setFkColumnName(String fkColumnName) {
		this.fkColumnName = fkColumnName;
	}
}
