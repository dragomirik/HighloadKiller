package fishgenerator.model;

public class Column {
	private String name;
	private int type;

	public Column(String name, int type) {
		this.name = name;
		this.setType(type);
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public int getType() {
		return type;
	}

	public void setType(int type) {
		this.type = type;
	}

}
